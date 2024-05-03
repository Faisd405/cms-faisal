export const handleApiErrorMessage = (error) => {
    return (
        error.response?.data?.message ||
        error.response?.status === null ||
        error.message
    )
}
