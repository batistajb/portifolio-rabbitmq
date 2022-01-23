import {Options} from "amqplib";
import {MethodDecoratorFactory} from "@loopback/core";

export interface RabbitmqSubscribeMetadata {
    exchange: string;
    routingKey: string | string[];
    queue? : string;
    queueOptions? : Options.AssertQueue
}

export const RABBTMQ_SUB_DECORATOR = 'rabbtimq-subscribe-metadata';

export function rabbitmqSubscribe(spec: RabbitmqSubscribeMetadata): MethodDecorator {
    return MethodDecoratorFactory.createDecorator<RabbitmqSubscribeMetadata>(
        RABBTMQ_SUB_DECORATOR, spec
    );
}
