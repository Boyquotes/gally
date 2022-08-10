import {
  apiUrl,
  authHeader,
  contentTypeHeader,
  languageHeader,
  tokenStorageKey,
} from '~/constants'
import { IResource, IResponseError, ISearchParameters, Method } from '~/types'
import { storageGet } from './storage'

import { getUrl } from './url'

export class ApiError extends Error {}

export function isApiError<T>(
  json: T | IResponseError
): json is IResponseError {
  return 'code' in json && 'message' in json
}

export function normalizeUrl(url = ''): string {
  if (process.env.NEXT_PUBLIC_LOCAL) {
    try {
      const urlObj = new URL(url)
      if (urlObj.origin === apiUrl) {
        if (urlObj.pathname === '/') {
          urlObj.pathname = '/index'
        }
        if (
          urlObj.pathname &&
          !urlObj.pathname.endsWith('.json') &&
          !urlObj.pathname.endsWith('.jsonld')
        ) {
          urlObj.pathname = `${urlObj.pathname}.json`
        }
        if (urlObj.pathname.endsWith('.jsonld')) {
          urlObj.pathname = `${urlObj.pathname.slice(0, -7)}.json`
        }
        if (!urlObj.pathname.startsWith('/mocks')) {
          urlObj.pathname = `/mocks${urlObj.pathname}`
        }
        url = urlObj.href
      }
    } catch (error) {
      // in that case just silent and don't transform the URL
    }
  }
  return url
}

export function getApiUrl(url = ''): string {
  if (!url.startsWith('http')) {
    if (!url.startsWith('/')) {
      url = `/${url}`
    }
    url = `${apiUrl}${url}`
  }
  return url
}

export async function fetchJson<T>(
  url: RequestInfo | URL,
  options: RequestInit = {}
): Promise<{ json: T; response: Response }> {
  if (!options.method || options.method === Method.GET) {
    url = normalizeUrl(url.toString())
  }
  const response = await fetch(url, options)
  const json = await response.json()
  return { json, response }
}

export function fetchApi<T>(
  language: string,
  resource: IResource | string,
  searchParameters: ISearchParameters = {},
  options: RequestInit = {},
  secure = true
): Promise<T> {
  const apiUrl =
    typeof resource === 'string' ? getApiUrl(resource) : getApiUrl(resource.url)
  const headers: Record<string, string> = {
    [languageHeader]: language,
    [contentTypeHeader]: 'application/json',
    ...(options.headers as Record<string, string>),
  }
  const token = storageGet(tokenStorageKey)
  if (secure && token) {
    headers[authHeader] = `Bearer ${token}`
  }
  return fetchJson<T>(getUrl(apiUrl, searchParameters), {
    ...options,
    headers: {
      [languageHeader]: language,
      [contentTypeHeader]: 'application/json',
      ...options.headers,
    },
  }).then(({ json }) => {
    if (isApiError(json)) {
      throw new ApiError(json.message)
    }
    return json
  })
}

export function removeEmptyParameters(
  searchParameters: ISearchParameters = {}
): ISearchParameters {
  return Object.fromEntries(
    Object.entries(searchParameters).filter(
      ([_, value]) => (value ?? '') !== ''
    )
  )
}
