import {bind, BindingScope} from "@loopback/core";
import {rabbitmqSubscribe} from "../decorators";
import {relation, repository} from "@loopback/repository";
import {FollowRepository, PostRepository} from "../repositories";
import {Message} from "amqplib";
import {pick} from "lodash";

@bind({scope: BindingScope.TRANSIENT})
export class PostSyncService {
    constructor(
        @repository(PostRepository) private postRepo: PostRepository,
        @repository(FollowRepository) private repoRelation: FollowRepository,
    ) {}

    @rabbitmqSubscribe({
        exchange: 'amq.topic',
        queue: 'micro-postter/sync-posts',
        routingKey: 'model.post.*'
    })
    async handler({data, message}: {data: any, message: Message}) {
        const action = this.getAction(message);
        const isModel = this.getIsModel(message);
        if (isModel) {
            const entity = this.createEntity(data);
            switch (action){
                case 'created':
                    await this.postRepo.create(entity);
                    break;
                case 'updated':
                    let post = await this.postRepo.findById(entity.id);

                    if(post) {
                        await this.postRepo.updateById(entity.id, entity)
                    } else {
                        await this.postRepo.create(entity)
                    }
                    break;
                case 'deleted':
                    await this.postRepo.deleteById(entity.id);
                    break;
            }
        }
    }

    protected getAction(message: Message) {
        return message.fields.routingKey.split('.')[2];
    }

    protected getIsModel(message: Message) {
        return message.fields.routingKey.split('.')[1] === this.postRepo.entityClass.name.toLowerCase();
    }

    protected createEntity(data: any){
        data.id = data.uuid;
        data.status = true;
        return pick(data, Object.keys(this.postRepo.entityClass.definition.properties))
    }
}
