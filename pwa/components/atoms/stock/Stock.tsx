import { useTranslation } from 'react-i18next'
import Tag from '~/components/atoms/form/Tag'

interface IProps {
  stock: boolean
}

function Stock(props: IProps): JSX.Element {
  const { stock } = props
  const { t } = useTranslation('common')
  const label = t(stock ? 'stock.inStock' : 'stock.outOfStock')

  return <Tag color={stock ? 'success' : 'error'}>{label}</Tag>
}

export default Stock