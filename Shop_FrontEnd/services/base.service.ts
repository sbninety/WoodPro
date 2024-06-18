import { useApi } from "~/composables/api";
import Api from "~/lib/api";

export class BaseService {
    readonly #api
    constructor() {
        this.#api = useApi()
    }

    get api(): Api {
        return this.#api
    }
}