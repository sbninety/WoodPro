import type { Cities } from "~/types/models/city";
import { BaseService } from "./base.service";

export const useCityService = () => new CityService();

class CityService extends BaseService {
    constructor() {
        super();
    }

    public async getAll(): Promise<ApiResponse<Cities>> {
        return this.api.get<ApiResponse<Cities>>('api/v1/cities');
    }
}