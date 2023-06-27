Models:
User{
    first_name
    last_name
    email
    password
}
Gallery{
    author(user)
    title
    description
    images(array)
    comments(array)
}
Image{
    link
}
Comment{
    author
    text
}
Controllers:
AuthController{
    login
    register
    logout
}
GalleryController{
    index
    create
    update
    show
    delete
}
CommentController{
    index
    create
    update
    show
    delete
}
