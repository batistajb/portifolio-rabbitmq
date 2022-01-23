import {inject} from '@loopback/core';
import {DefaultCrudRepository} from '@loopback/repository';
import {Esv7Datasource} from '../datasources';
import {Post} from '../models';

export class PostRepository extends DefaultCrudRepository<
  Post,
  typeof Post.prototype.id
> {
  constructor(@inject('datasources.esv7') dataSource: Esv7Datasource){
    super(Post, dataSource);
  }
}
