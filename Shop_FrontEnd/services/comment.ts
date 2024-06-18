import { BaseService } from "./base.service";

export const useCommentService = () => new CommentService();

class CommentService extends BaseService {
    constructor() {
        super();
    }

    public async create(params: any): Promise<ApiResponse<any>> {
        return this.api.post<ApiResponse<any>>('api/v1/comments', params);
    }
}