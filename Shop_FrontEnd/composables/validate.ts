export const useValidate = () => {
    const phoneNumber = (value: string) => {
        if (!value) {
            return true;
        }
        return /(84|0[3|5|7|8|9])+([0-9]{8})\b/g.test(value)
    }

    const sameAsPassword = (value: string, pass: string) => {
        if (!value) {
            return true;
        }
        if (value === pass) {
            return true;
        }
        return false;
    }

    return { phoneNumber, sameAsPassword }
}