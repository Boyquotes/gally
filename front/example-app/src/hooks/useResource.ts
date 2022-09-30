import { useContext, useMemo } from 'react'
import { IResource, getResource, schemaContext } from 'shared'

export function useResource(resourceName: string): IResource {
  const api = useContext(schemaContext)

  return useMemo(() => {
    return getResource(api, resourceName)
  }, [api, resourceName])
}