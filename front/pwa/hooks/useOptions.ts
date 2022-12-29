import { useTranslation } from 'next-i18next'
import { useCallback, useMemo } from 'react'

import {
  IApiSchemaOptions,
  IFetchApi,
  IField,
  IHydraMember,
  IHydraResponse,
  IOptions,
  IOptionsContext,
  getOptionsFromApiSchema,
  getOptionsFromResource,
  getReferencedResource,
  hasFieldOptions,
  isDropdownStaticOptions,
  isError,
  isReferenceField,
} from 'gally-admin-shared'
import { selectApi, useAppSelector } from '~/store'
import { useSingletonLoader } from './useSingletonLoader'

export function useOptions(): IOptionsContext {
  const { fetch, map, statuses } =
    useSingletonLoader<IOptions<string | number>>()
  const api = useAppSelector(selectApi)
  const { t } = useTranslation('options')

  const load = useCallback(
    (field: IField) => {
      const id = field.property['@id']
      return fetch(id, async (fetchApi: IFetchApi) => {
        if (hasFieldOptions(field)) {
          // get options from the schema
          if (isDropdownStaticOptions(field.elasticsuite?.options)) {
            // static options
            const options = field.elasticsuite.options.values.map((option) => ({
              ...option,
              label: t(String(option.label)),
            }))
            return options
          }
          // options from api
          const response = await fetchApi(field.elasticsuite.options.api_rest)
          if (!isError(response)) {
            return getOptionsFromApiSchema(
              response as IHydraResponse<IApiSchemaOptions>
            )
          }
          throw new Error('error')
        } else if (isReferenceField(field)) {
          // get options by loading all items of the referenced field
          const response = await fetchApi(
            getReferencedResource(api, field)?.url
          )
          if (!isError(response)) {
            return getOptionsFromResource(
              response as IHydraResponse<IHydraMember>
            )
          }
          throw new Error('error')
        }
      })
    },
    [api, fetch, t]
  )

  return useMemo(
    () => ({ fieldOptions: map, load, statuses }),
    [load, map, statuses]
  )
}
