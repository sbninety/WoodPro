import { BaseService } from "~/services/base.service";
import type { User } from "~/types/models/user";

export const useAuthService = () => new AuthService()

class AuthService extends BaseService {
    constructor() {
        super();
    }

    public async login(params?: object): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('/api/v1/login', params)
    }

    public async register(params: object): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('/api/v1/register', params)
    }

    public async forgot(params: object): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('/api/v1/forgot', params)
    }

    public async checkToken(params: object): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/reset', params)
    }

    public async resetPassword(params: any): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('/api/v1/reset', params)
    }

    public async logout(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/logout');
    }
}
