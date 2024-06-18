import { BaseService } from "./base.service";

export const useOrderService = () => new OrderService();

class OrderService extends BaseService {
    constructor() {
        super();
    }

    public async create(params: object): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/orders', params);
    }

    public async getOrderByUser(id: number): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/user/' + id + '/orders');
    }

    public async update(id: number, params: any): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/orders/' + id, params);
    }

    public async getRevenue(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/orders/revenue');
    }

    public async count(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/orders/total');
    }

    public async search(params: any): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/orders/search', params);
    }

    public async export(id: number): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/orders/' + id + '/export');
    }
}