import { BaseService } from "./base.service";

export const usePaymentService = () => new PaymentService();

class PaymentService extends BaseService {
    constructor() {
        super();
    }

    public async init(params: any): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/payment/init', params);
    }
}