import { Section } from '@/components/section/Section'
import { Container } from '@/components/container/Container'
import styles from './Layout.module.scss'

export const Layout = ({ children }: LayoutProps) => (
  <div className={styles.wrapper}>
    <Section>
      <Container>{children}</Container>
    </Section>
  </div>
)
