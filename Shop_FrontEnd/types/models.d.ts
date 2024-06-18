export { }
declare global {
    export namespace models {
        export interface User {
            // columns
            id: number
            email: string
            name: string | null
            avatar: string | null
            job: string
            stripe: string | null
            is_sub: boolean
            created_at: Date | null
            updated_at: Date | null
            // mutators
            is_trial: boolean
            first_name: string
            has_active_session: boolean
            session: Session
            location: Array<unknown>
        }

        export type Users = User[]
        export type UserResults = Modify<api.MetApiResults, { data: Users }>
    }
}