import {Entity, model, property} from '@loopback/repository';
import {FollowersPosts} from "./follow.model";

export interface PostsFollows {
  user_uuid: string
}

@model({settings: {strict: false}})
export class Post extends Entity {
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
  uuid: string;

  @property({
    type: 'string',
    required: true,
  })
  content: string;

  @property({
    type: 'string',
    required: true,
  })
  user_id: string;

  @property({
    type: 'string',
  })
  user_name?: string;

  @property({
    type: 'string',
    required: true,
  })
  url: string;

  @property({
    type: 'number',
    default: 0,
  })
  comments_count?: number;

  @property({
    type: 'number',
    default: 0,
  })
  likes_count?: number;

  @property({
    type: 'string',
    required: true,
  })
  type: string;

  @property({
    type: 'string',
    default: false,
    required: false,
  })
  status: boolean;

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
          id: {
            type: "string"
          },
          user_uuid: {
            type: "string"
          },
          user_uuid_follow: {
            type: "string"
          },
          user_name_follow: {
            type: "string"
          },
        }
      },
      uniqueItems: true
    }
  })
  follows: FollowersPosts

  [prop: string]: any;

  constructor(data?: Partial<Post>) {
    super(data);
  }
}
