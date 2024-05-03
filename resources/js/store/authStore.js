import { AxiosError } from 'axios'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user', null)),
        token: localStorage.getItem('token', null),
        tokenApi: localStorage.getItem('token_api', null)
    }),
    actions: {
        async login(email, password) {
            try {
                const response = await axios.post('/api/login', {
                    email: email,
                    password: password
                })

                const responseData = await response.data

                if (response.status !== 200) {
                    throw new Error(responseData.message)
                }

                this.user = responseData.data.user
                this.token = responseData.data.token
                localStorage.setItem(
                    'user',
                    JSON.stringify(responseData.data.user)
                )
                localStorage.setItem('token', responseData.data.token)

                return response
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async loginAsPengguna(email, password) {
            try {
                const responseMyEtask = await axios.post(
                    'https://laporan.4visionmedia.com/api/data/login',
                    {
                        email: email,
                        password: password
                    }
                )

                let user = responseMyEtask.data.user
                let tokenApi = responseMyEtask.data.token

                if (responseMyEtask.data.success === true) {
                    const loginPengguna = await this.loginPengguna(
                        {
                            id_api: user.id,
                            name: user.name,
                            email: user.email,
                            jabatan: user.jabatan,
                            token: user.token
                        },
                        password,
                        responseMyEtask.data.success
                    )

                    this.user = loginPengguna.data.user
                    this.token = loginPengguna.data.token
                    this.tokenApi = tokenApi.token

                    localStorage.setItem(
                        'user',
                        JSON.stringify(loginPengguna.data.user)
                    )
                    localStorage.setItem('token', loginPengguna.data.token)
                    localStorage.setItem('token_api', tokenApi.token)

                    return loginPengguna
                }
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async loginPengguna(data, password, success = false) {
            try {
                const loginPengguna = await axios.post('/api/loginapi', {
                    id_api: data.id,
                    name: data.name,
                    email: data.email,
                    password: password,
                    success: success,
                    jabatan: data.jabatan,
                    token: data.token
                })

                const responseData = await loginPengguna.data

                if (loginPengguna.status !== 200) {
                    throw new Error(responseData.message)
                }

                return responseData
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async getUser() {
            try {
                const getUser = await axios.get('/api/user')

                this.users = getUser.data

                return getUser.data
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async getUserMyEtask() {
            try {
                const userMyEtask = await axios.get(
                    'https://laporan.4visionmedia.com/api/user',
                    {
                        headers: {
                            Authorization: this.tokenApi
                        }
                    }
                )

                this.users = userMyEtask.data

                return userMyEtask.data
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async syncProfileFromMyEtask() {
            try {
                const profileEtask = await axios.get(
                    `https://laporan.4visionmedia.com/api/user`,
                    {
                        headers: { Authorization: this.tokenApi }
                    }
                )

                let updateUser = profileEtask.data

                await axios.put(`/api/pengguna/` + this.user.id, {
                    name: updateUser.username,
                    jabatan: updateUser.jabatan.nama,
                    id_api: updateUser.id
                })

                await this.getUser()
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        },
        async logout() {
            try {
                const response = await axios.post('/api/logout')

                const responseData = await response.data

                if (response.status !== 200) {
                    throw new Error(responseData.message)
                }

                this.user = null
                this.token = null
                localStorage.removeItem('user')
                localStorage.removeItem('token')

                return response
            } catch (error) {
                let message = 'Internal Error'
                if (error instanceof AxiosError)
                    message = error.response.data.message

                throw new Error(message)
            }
        }
    }
})
