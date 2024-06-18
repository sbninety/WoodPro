import { BaseService } from "./base.service";

export const useUserService = () => new UserService();

class UserService extends BaseService {
    constructor() {
        super();
    }

    public async countCustomer(): Promise<ApiResponse<any>> {
        return this.api.get<ApiResponse<any>>('api/v1/customers/total');
    }

    public async getStaffLog(): Promise<ApiResponse<any>> {
        return this.api.get<ApiResponse<any>>('api/v1/staff/log');
    }
}