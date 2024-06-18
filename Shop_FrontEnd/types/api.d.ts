export { }
declare global {
    export interface ApiResponse<T> {
        status: string
        message: string
        errors: string
        data: T
    }
}