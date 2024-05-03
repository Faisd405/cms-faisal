export function toCurrency(value) {
    if (typeof value !== 'number') {
        return value
    }
    var formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    })
    return formatter.format(value)
}
