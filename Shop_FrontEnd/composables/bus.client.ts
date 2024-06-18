import mitt from "mitt";

type ApplicationEvents = {
    'user:registered': any
};

const emitter = mitt<ApplicationEvents>()

export const useEventBus = () => {
    const $event = emitter.emit
    const $listen = emitter.on

    return { $event, $listen }
}