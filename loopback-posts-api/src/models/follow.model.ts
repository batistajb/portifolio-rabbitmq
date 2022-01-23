import {Entity, model, property} from '@loopback/repository';
import {PostsFollows} from "./post.model";

export interface FollowersPosts {
  id: string,
  user_uuid: string,
  user_name_follow: string,
  user_uuid_follow: string,
  created_at: string,
  updated_at: string,
}

@model()
export class Follow extends Entity {
  @property({
    type: 'string',
    id: true,
    generated: false,
    required: true
  })
  id: string;

  @property({
    type: 'string',
    required: true
  })
  user_uuid: string;

  @property({
    type: 'string',
    required: true,
  })
  user_name_follower: string;

  @property({
    type: 'string',
    required: true,
  })
  user_uuid_follower: string;

  @property({
    type: 'string',
    default: new Date().toISOString(),
  })
  created_at?: string;

  @property({
    type: 'string',
    default: new Date().toISOString(),
  })
  updated_at?: string;


  @property({
    type: 'object',
    jsonSchema: {
      type: 'array',
      items: {
        type: 'object',
        properties: {
          user_uuid: {
            type: "string"
          }
        }
      },
      uniqueItems: false
    }
  })
  follows: PostsFollows

  [prop: string]: any;

  constructor(data?: Partial<Follow>) {
    super(data);
  }
}
