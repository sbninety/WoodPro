import { AttributeText } from './_attributes'
import { DefaultText } from './_default'
import { userScreen } from "~/locales/_user";
import { MessageText } from "~/locales/_messages";
import { productScreen } from "./_product"

export const locale = {
    ...DefaultText.DEFAULT,
    ...userScreen.LABEL,
    ...productScreen.LABEL,
    ...MessageText,
    ...{
        ATTRIBUTE: {
            ...AttributeText.ATTRIBUTE,
            ...userScreen.ATTRIBUTE,
            ...productScreen.ATTRIBUTE,
        }
    }
}
