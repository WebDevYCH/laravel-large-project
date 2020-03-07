<?php


namespace LargeLaravel\Containers\Book\Actions;


use Carbon\Carbon;
use LargeLaravel\Containers\Book\Collections\BookCollection;
use LargeLaravel\Containers\Book\DTO\BookDTO;
use LargeLaravel\Containers\Book\Proxies\BookEloquentProxy;
use LargeLaravel\Containers\Book\Subactions\Interfaces\GetBookListActionInterface;
use LargeLaravel\Core\Http\Requests\API\Interfaces\PaginateRequestInterface;

class GetBookListAction implements GetBookListActionInterface
{
    private BookEloquentProxy $bookEloquentProxy;

    public function __construct(BookEloquentProxy $bookEloquentProxy)
    {
        $this->bookEloquentProxy = $bookEloquentProxy;
    }

    public function execute(PaginateRequestInterface $paginateRequest): BookCollection
    {
        $bookCollection = [];
        $bookList = $this->bookEloquentProxy->findAll(
            [],
            $paginateRequest->getLimit(),
            $paginateRequest->getOffset()
        );

        foreach ($bookList as $book) {
            $book['publish_date'] = Carbon::parse($book['publish_date']);
            $bookCollection[] = new BookDTO($book);
        }

        return new BookCollection(...$bookCollection);
    }
}