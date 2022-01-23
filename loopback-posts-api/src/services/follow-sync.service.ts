import {bind, BindingScope} from "@loopback/core";
import {repository} from "@loopback/repository";
import {FollowRepository, PostRepository} from "../repositories";
import {rabbitmqSubscribe} from "../decorators";
import {Message} from "amqplib";

@bind({scope: BindingScope.TRANSIENT})
export class FollowSyncService {
    constructor(
        @repository(FollowRepository) private repo: FollowRepository,
        @repository(PostRepository) private repoPost: PostRepository,
    ) {}


    @rabbitmqSubscribe({
        exchange: 'amq.topic',
        queue: 'micro-postter/sync-follow',
        routingKey: 'model.follow.*'
    })
    async handlerFollow({data, message}: {data: any, message: Message}) {
        const action = this.getAction(message);
        const isModel = this.getIsModel(message);
        const relation = 'follows';
        if (isModel) {
            switch (action) {
                case 'created':
                   // await this.repo.create(entity);
                    break;
                case 'deleted':
                   // await this.repo.deleteById(entity.id);
                    break;
            }
        }
       /* const fieldsRelation = this.createRelation(data, relation);
        const collection = await this.repoPost.find({
            where: {
                or: [{user_uuid:data.user_uuid}]
            }
        });
        await this.repo.create(data,{[relation]: collection});*/
    }

    protected createRelation(data: any, relation: string) {
        return Object.keys(
            this.repoPost.modelClass.definition.properties[relation].jsonSchema.items.properties
        ).reduce((obj: any, field: string) => {
                obj[field] = true;
                return obj;
            },{}
        )
    }

    protected getAction(message: Message) {
        return message.fields.routingKey.split('.')[2];
    }

    protected getIsModel(message: Message) {
        return message.fields.routingKey.split('.')[1] === this.repo.entityClass.name.toLowerCase();
    }
}
