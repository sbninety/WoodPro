export const useUtils = () => {
    const MONTHS = [
        'Tháng 1',
        'Tháng 2',
        'Tháng 3',
        'Tháng 4',
        'Tháng 5',
        'Tháng 6',
        'Tháng 7',
        'Tháng 8',
        'Tháng 9',
        'Tháng 10',
        'Tháng 11',
        'Tháng 12'
    ];

    const months = (config: any) => {
        var cfg = config || {};
        var count = cfg.count || 12;
        var section = cfg.section;
        var values = [];
        var i, value;

        for (i = 0; i < count; ++i) {
            value = MONTHS[Math.ceil(i) % 12];
            values.push(value.substring(0, section));
        }

        return values;
    }

    const price = (x: number) => {
        let val = (x / 1).toFixed(0).replace(".", ",");
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    const onlyNumber = (evt: KeyboardEvent) => {
        const keysAllowed: string[] = [
            "0",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            ".",
        ];
        const keyPressed: string = evt.key;

        if (!keysAllowed.includes(keyPressed)) {
            evt.preventDefault();
        }
    };

    return { months, price, onlyNumber };
}