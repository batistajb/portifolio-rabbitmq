import {PostterPostsApiApplication} from '../..';
import {
  createRestAppClient,
  givenHttpServerConfig,
  Client,
} from '@loopback/testlab';

export async function setupApplication(): Promise<AppWithClient> {
  const restConfig = givenHttpServerConfig({
    // Customize the server configuration here.
    // Empty values (undefined, '') will be ignored by the helper.
    //
    // host: process.env.HOST,
    // port: +process.env.PORT,
  });

  const app = new PostterPostsApiApplication({
    rest: restConfig,
  });

  await app.boot();
  await app.start();

  // @ts-ignore
  const client = createRestAppClient(app);

  return {app, client};
}

export interface AppWithClient {
  app: PostterPostsApiApplication;
  client: Client;
}
