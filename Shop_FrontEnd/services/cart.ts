import { BaseService } from "./base.service";

export const useCartService = () => new CartServcie()

class CartServcie extends BaseService {
    constructor() {
        super();
    }

    public async getCartByUser(id: number): Promise<ApiResponse<any>> {
        return this.api.get<ApiResponse<any>>('/api/v1/user/' + id + '/cart');
    }

    public async store(params: object): Promise<ApiResponse<any>> {
        return this.api.post<ApiResponse<any>>('/api/v1/cart', params);
    }

    public async update(params: any, id: number): Promise<ApiResponse<any>> {
        return this.api.post<ApiResponse<any>>('/api/v1/cart/' + id, params);
    }

    public async delete(id: number): Promise<ApiResponse<any>> {
        return this.api.delete<ApiResponse<any>>('/api/v1/cart/' + id);
    }
}