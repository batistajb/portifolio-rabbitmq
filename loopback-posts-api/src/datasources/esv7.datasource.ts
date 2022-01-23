import {lifeCycleObserver, LifeCycleObserver, ValueOrPromise} from '@loopback/core';
import {juggler} from '@loopback/repository';
import dbConfig from './esv7.config';

@lifeCycleObserver('datasource')
export class Esv7Datasource extends juggler.DataSource
  implements LifeCycleObserver {
  static dataSourceName = 'esv7';
  static readonly defaultConfig = dbConfig;
  constructor(config = dbConfig) {
    super(config);
  }


  /**
   * Start the datasource when application is started
   */
  start(): ValueOrPromise<void> {
    // Add your logic here to be invoked when the application is started
  }

  stop(): ValueOrPromise<void> {
    return super.disconnect();
  }
}
