import Pagination from './Pagination'
import { renderWithProviders } from '~/services/tests'

describe('Pagination', () => {
  it('should match snapshot for bottom pagination', () => {
    const { container } = renderWithProviders(
      <Pagination
        isBottom
        totalPages={10}
        currentPage={1}
        rowsPerPage={5}
        rowsPerPageOptions={[]}
        onRowsPerPageChange={null}
        onPageChange={jest.fn()}
      />
    )
    expect(container).toMatchSnapshot()
  })

  it('should match snapshot for top pagination', () => {
    const { container } = renderWithProviders(
      <Pagination
        isBottom={false}
        totalPages={10}
        currentPage={1}
        rowsPerPage={5}
        rowsPerPageOptions={[]}
        onRowsPerPageChange={null}
        onPageChange={jest.fn()}
      />
    )
    expect(container).toMatchSnapshot()
  })
})