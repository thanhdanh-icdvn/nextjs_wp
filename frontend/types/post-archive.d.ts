

type Post = PostType & { id: string }

type Pagination = {
  currentPage: number
  totalPages: number
  href: string
}

type PostsArchiveProps = {
  posts: Post[]
  pagination: Pagination
}
