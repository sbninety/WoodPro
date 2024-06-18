import { BaseService } from "./base.service";

export const useProfileService = () => new ProfileService();

class ProfileService extends BaseService {
    constructor() {
        super();
    }

    public async getProfile(id: number): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/user/' + id + '/profile');
    }

    public async update(params: object, id: number): Promise<ApiResponse<any>> {
        return await this.api.put<ApiResponse<any>>('api/v1/user/' + id + '/profile', params);
    }
}