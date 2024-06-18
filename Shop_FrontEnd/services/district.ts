import type { Districts } from "~/types/models/district";
import { BaseService } from "./base.service";

export const useDistrictService = () => new DistrictService();

class DistrictService extends BaseService {
    constructor() {
        super();
    }

    public async getDistrictByCityId(id: number): Promise<ApiResponse<Districts>> {
        return this.api.get<ApiResponse<Districts>>('api/v1/cities/' + id + '/districts');
    }
}