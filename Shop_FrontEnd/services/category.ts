import { BaseService } from "./base.service";

export const useCategoryService = () => new CategoryService()

class CategoryService extends BaseService {
    constructor() {
        super();
    }

    public async getAll(): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/categories')
    }

    public async getCategoryBySlug(slug: string): Promise<ApiResponse<any>> {
        return await this.api.get<ApiResponse<any>>('/api/v1/categories/' + slug)
    }
}