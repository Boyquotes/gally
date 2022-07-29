import Catalogs from './Catalogs'
import { renderWithProviders } from '~/services'
import catalog from '../../../../public/mocks/catalog.json'

describe('Catalogs match snapshot', () => {
  it('Catalogs simple', () => {
    const { container } = renderWithProviders(<Catalogs content={catalog} />)
    expect(container).toMatchSnapshot()
  })
})