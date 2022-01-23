import {Application, Context, CoreBindings, inject, MetadataInspector, Server} from "@loopback/core";
import {Channel, ConfirmChannel, Message, Options} from "amqplib";
import {RabbitmqBinding} from "./keys";
import {AmqpConnectionManager, AmqpConnectionManagerOptions, ChannelWrapper, connect} from "amqp-connection-manager";
import {RabbitmqSubscribeMetadata, RABBTMQ_SUB_DECORATOR} from "../decorators";
import {Binding} from "@loopback/boot";

export enum ResponseEnum {
    ACK,
    REQUEUE,
    NACK
}

export interface RabbitmqConfig {
    uri: string;
    options ?: AmqpConnectionManagerOptions;
    exchanges ?: {name: string, type: string, options?: Options.AssertExchange};
    defaultHandlerError?: ResponseEnum
}

export class RabbitmqServer extends Context implements Server{
    private _listening: boolean;
    private _conn: AmqpConnectionManager;
    private _channelManager: ChannelWrapper;
    channel: Channel;

    constructor(
        @inject(RabbitmqBinding.CONFIG) private config: RabbitmqConfig,
        @inject(CoreBindings.APPLICATION_INSTANCE) public app: Application,
    ) {
        super(app);
    }

    private async setupExchanges() {
        return this.channelManager.addSetup( async (channel: ConfirmChannel) => {
            if(!this.config.exchanges){
                return;
            }
            // @ts-ignore
            await Promise.all(this.config.exchanges.map((exchange) => {
                channel.assertExchange(exchange.name, exchange.type, exchange.options);
            }))
        });
    }

    private bindSubscribers() {
        this
            .getSubscribers()
            .map(async (item) => {
               await this.channelManager.addSetup( async (channel: ConfirmChannel) => {
                   const {exchange, queue, routingKey, queueOptions} = item.metadata;
                   const assertQueue = await channel.assertQueue(
                       queue ?? '',
                       queueOptions ?? undefined
                   );
                   const routingKeys = Array.isArray(routingKey) ? routingKey : [routingKey];
                   await Promise.all(
                       routingKeys.map((x)=> channel.bindQueue(assertQueue.queue, exchange, x))
                   );
                   await this.consume({
                       channel,
                       queue: assertQueue.queue,
                       method: item.method
                   })
                });
            });
    }

    private async consume({channel, queue, method}: {channel: ConfirmChannel, queue: string, method: Function}) {
        await channel.consume(queue, async message => {
            try {
                if (! message){
                    throw new Error('Receiver null message');
                }
                const content = message.content;
                if (content) {
                    let data;
                    try {
                        data = JSON.parse(content.toString());
                    }catch (e) {
                        data = null;
                    }
                    const responseType = await method({data, message, channel});
                    RabbitmqServer.dispatchReponse(channel, message, responseType);
                }
            } catch (e) {
                console.error(e);
                if (!message) {
                    return;
                }
                RabbitmqServer.dispatchReponse(channel, message, this.config?.defaultHandlerError);
            }
        });
    }

    private getSubscribers(): {method: Function, metadata: RabbitmqSubscribeMetadata}[] {
        const bindings: Array<Readonly<Binding>> = this.find('services.*');
        return bindings
            .map(
                binding => {
                    const metadata = MetadataInspector.getAllMethodMetadata<RabbitmqSubscribeMetadata>(
                        RABBTMQ_SUB_DECORATOR, binding.valueConstructor?.prototype
                    );
                    if(!metadata) {
                        return [];
                    }
                    const methods = [];
                    for (const methodName  in metadata) {
                        if (!Object.prototype.hasOwnProperty.call(metadata, methodName)) {return}
                        const service = this.getSync(binding.key) as any;
                        methods.push({
                           method: service[methodName].bind(service),
                           metadata: metadata[methodName]
                        });
                    }
                    return methods;
                }
            )
            .reduce((collection: any, item: any) => {
                collection.push(...item);
                return collection;
            },[]);
    }

    private static dispatchReponse(channel: Channel, message: Message, resposeType?: ResponseEnum) {
        switch (resposeType) {
            case ResponseEnum.REQUEUE:
                channel.nack(message, false, true);
                break;
            case ResponseEnum.NACK:
                channel.nack(message, false, false);
                break;
            case ResponseEnum.ACK:
            default:
                channel.ack(message);
        }
    }

    async start(): Promise<void> {
        this._conn = connect([this.config.uri], this.config.options);
        this._channelManager = this.conn.createChannel();
        this.channelManager.on('connect', ()=>{
            this._listening = true;
            console.log("connect manager")
        });
        this.channelManager.on('error', (error)=>{
            this._listening = false;
            console.error("connect manager fail", error)
        });
        await this.setupExchanges();
        await this.bindSubscribers();
    }

    async stop(): Promise<void> {
        await this._conn.close();
        this._listening = false;
    }

    get listening(): boolean {
        return this._listening;
    }

    get conn(): AmqpConnectionManager{
        return this._conn;
    }

    get channelManager(): ChannelWrapper{
        return this._channelManager;
    }
}
