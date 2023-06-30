import styles from './Section.module.scss'

export const Section = ({ children }: SectionProps) => (
  <div className={styles.section}>{children}</div>
)
