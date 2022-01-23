import {CoreBindings} from "@loopback/core";
import {RabbitmqConfig} from "./rabbitmq.server";

export namespace RabbitmqBinding{
   export const CONFIG = CoreBindings.APPLICATION_CONFIG.deepProperty<RabbitmqConfig>('rabbitmq');
}
