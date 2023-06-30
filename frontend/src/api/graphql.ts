import axios, { AxiosRequestConfig } from 'axios'

const client = axios.create({
  baseURL: `http://localhost/graphql`,
})

client.interceptors.response.use(
  ({ data }) => data,
  (error) => Promise.reject(error)
)

function graphQL(
  query: string,
  variables: Record<string, unknown>,
  options?: AxiosRequestConfig
) {
  return client.post('/', { query, variables }, options)
}

export default graphQL
