export const useTrans = () => {
    const { t: trans, n: numberFormats, d: datetimeFormats } = useNuxtApp().$i18n.global
    return {
        trans
    }
}