import { BaseService } from "./base.service";

export const useProductService = () => new ProductService()

class ProductService extends BaseService {
    constructor() {
        super();
    }

    public async getSpeical(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/special');
    }

    public async getSale(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/sale');
    }

    public async getSuggest(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/suggest');
    }

    public async getOther(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/other');
    }

    public async getAll(params?: object): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products', params);
    }

    public async getProductByCategoryId(id: number): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/categories/' + id + '/products');
    }

    public async getProductBySlug(slug: string): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/' + slug);
    }

    public async count(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/total');
    }

    public async updateStatus(params: any, id: number): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/products/' + id, params);
    }

    public async create(params: any): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/products', params);
    }

    public async getProductById(id: number): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/' + id + '/detail');
    }

    public async update(params: any, id: number): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('api/v1/products/' + id, params);
    }

    public async getBestSellingProducts(params: any): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/best-selling-products', params);
    }

    public async getWorstSellingProducts(params: any): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('api/v1/products/worst-selling-products', params);
    }

    public async exportProducts(params?: object): Promise<ApiResponse<any>> {
        return await this.api.export<ApiResponse<any>>('/api/v1/products/export', params);
    }

    public async importProducts(params: any): Promise<ApiResponse<any>> {
        return await this.api.post<ApiResponse<any>>('/api/v1/products/import', params);
    }
}