export function transformSlug(value) {
    return value.split(' ').join('-').toLowerCase()
}
