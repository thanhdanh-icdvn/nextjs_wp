type Author = {
  name: string
  slug: string
  avatar: {
    url: string
  }
}
type AvatarProps = Author
type Term = {
  name: string
  slug: string
}

type PostType = {
  id: string
  author: { node: Author }
  date: string
  content: string
  slug: string
  title: string
  categories: { edges: { node: Term }[] }
  tags: { edges: { node: Term }[] }
}

type CategoryProps = {
  name: string
  slug: string
}

type PostProps = {
  post: PostType
  isExcerpt: boolean
}
