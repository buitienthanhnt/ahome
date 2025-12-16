<?php

namespace App\Api;

use App\Api\Convert\ConvertComment;
use App\Models\Comment;
use App\Models\Paper;
use Illuminate\Http\Request;

class CommentApi
{
    protected $paperRepository;
    protected $comment;

    protected $convertComment;
    protected $responseApi;

    protected $request;

    function __construct(
        Comment $comment,
        ConvertComment $convertComment,
        ResponseApi $responseApi,
        Request $request,
        PaperRepository $paperRepository
    ) {
        $this->paperRepository = $paperRepository;
        $this->comment = $comment;
        $this->convertComment = $convertComment;
        $this->responseApi = $responseApi;
        $this->request = $request;
    }

    function getCommentOfPaper($paper_id)
    {
        /**
         * @var Paper $paper
         */
        $paper = $this->paperRepository->getById($paper_id);
        $comments = $paper->getCommentPaginate($this->request->get('limit', 12));
        return $this->convertComment->convertPaginate($comments);
    }

    function getCommentChildrent(int $comment_id)
    {
        /**
         * @var Comment $comment
         */
        $comment = $this->comment->find($comment_id);
        if (!$comment) {
            return response('', 405);
        }
        $childrent = $comment->getChildrentPaginate($this->request->get('limit', 12));
        return $this->convertComment->convertPaginate($childrent);
    }

    function addComment() {}
}
