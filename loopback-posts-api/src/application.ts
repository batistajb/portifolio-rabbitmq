import {BootMixin} from '@loopback/boot';
import {Application, ApplicationConfig} from '@loopback/core';
import {RestExplorerComponent} from './components';
import {RepositoryMixin} from '@loopback/repository';
import {RestBindings, RestComponent, RestServer} from '@loopback/rest';
import {ServiceMixin} from '@loopback/service-proxy';
import path from 'path';
import {MySequence} from './sequence';
import {RabbitmqServer} from "./servers";
import {RestExplorerBindings} from "@loopback/rest-explorer";

export {ApplicationConfig};

export class PostterPostsApiApplication extends BootMixin(
  ServiceMixin(RepositoryMixin(Application)),
) {
  constructor(options: ApplicationConfig = {}) {
    super(options);

    options.rest.sequence = MySequence;
    this.component(RestComponent);
    const restServer = this.getSync<RestServer>(RestBindings.SERVER);
    restServer.static('/', path.join(__dirname, '../public'));

    //customize config swagger
    this.bind(RestExplorerBindings.CONFIG).to({
      path: '/explorer'
    });
    this.component(RestExplorerComponent);

    this.projectRoot = __dirname;
    this.bootOptions = {
      controllers: {
        dirs: ['controllers'],
        extensions: ['.controller.js'],
        nested: true,
      },
    };
    this.server(RabbitmqServer)
  }
}
