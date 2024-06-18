export interface Product {
    id: number
    category_id: number
    store_id: number
    slug: string
    name: string
    price: number
    discount: number
    quantity: number
    special: boolean
    description: string
    published: number
    draft: string
    created_at: string
    updated_at: string
}