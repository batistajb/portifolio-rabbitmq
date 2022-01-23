import {inject} from '@loopback/core';
import {DefaultCrudRepository} from '@loopback/repository';
import {Esv7Datasource} from '../datasources';
import {Follow} from '../models';

export class FollowRepository extends DefaultCrudRepository<
  Follow,
  typeof Follow.prototype.id
> {
  constructor(@inject('datasources.esv7') dataSource: Esv7Datasource){
    super(Follow, dataSource);
  }
}
