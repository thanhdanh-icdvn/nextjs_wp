import { GetStaticPropsResult } from 'next'

import ArchivesPage, {
  getStaticProps as getPostsArchiveStaticProps,
} from '@/pages/page/[page]'
export default ArchivesPage

export const getStaticProps = async (
  ctx: GetStaticPropsResult<PostsArchiveProps>
) => getPostsArchiveStaticProps({ ...ctx, params: { page: '1' } })
