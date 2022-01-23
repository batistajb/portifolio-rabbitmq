import datasource from './datasources.json'

export default {
    ...datasource,
    debug: process.env.APP_ENV === 'dev',
    configuration: {
        node: process.env.ELASTICSEARCH_HOST,
        requestTimeout: process.env.ELASTICSEARCH_REQUEST_TIMEOUT,
        pingTimeout: process.env.ELASTICSEARCH_PING_TIMEOUT
    }
};
