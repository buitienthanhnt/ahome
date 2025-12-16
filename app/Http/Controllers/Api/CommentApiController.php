<?php

namespace App\Http\Controllers\Api;

use App\Api\CommentApi;
use App\Api\CommentRepository;
use App\Api\Convert\ConvertComment;
use App\Api\Data\ResponseData;
use App\Api\ResponseApi;
use App\Http\Controllers\Controller;
use App\Http\Exception\FormValidationException;
use App\Http\Validation\CommentForm;
use App\Http\Validation\CommentLikeForm;
use App\Models\Comment;
use App\Models\CommentInterface;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;

class CommentApiController extends Controller implements CommentApiControllerInterface
{
    protected $request;

    /**
     * @var Paper $paper
     */
    protected $paper;
    protected $comment;

    protected $responseData;
    protected $responseApi;
    protected $commentApi;

    protected $commentRepository;

    protected $commentForm;
    protected $commentLikeForm;

    protected $convertComment;

    function __construct(
        Request $request,
        Paper $paper,
        Comment $comment,
        CommentApi $commentApi,
        CommentRepository $commentRepository,
        ResponseApi $responseApi,
        ResponseData $responseData,
        CommentForm $commentForm,
        CommentLikeForm $commentLikeForm,
        ConvertComment $convertComment
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->comment = $comment;
        $this->responseData = $responseData;
        $this->responseApi = $responseApi;
        $this->commentApi = $commentApi;
        $this->commentRepository = $commentRepository;
        $this->commentForm = $commentForm;
        $this->commentLikeForm = $commentLikeForm;
        $this->convertComment = $convertComment;
    }

    /**
     * truyền thêm parent_id để phản hồi 1 comment.
     * @param int $paper_id
     * @return
     */
    function paperAddComment($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        if (!$paper) {
            return $this->responseApi->setStatusCode(404)->setResponse($this->responseData->setMessage('paper does not exist!'));
        }
        if (
            (!$email = $this->request->get(CommentInterface::ATTR_EMAIL)) ||
            (!$name = $this->request->get(CommentInterface::ATTR_NAME)) ||
            (!$content = $this->request->get(CommentInterface::ATTR_CONTENT))
        ) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage('require data not found!'));
        }
        $parentComment = $this->request->get(CommentInterface::ATTR_PARENT_ID, null);
        try {
            if ($parentComment && !$this->comment->find($parentComment)) {
                return $this->responseApi->setStatusCode(404)->setResponse($this->responseData->setMessage('parent of comment not found!'));
            }
        } catch (\Throwable $th) {
            return $this->responseApi->setStatusCode(404)->setResponse($this->responseData->setMessage('parent of comment not found!'));
        }

        $commentData = [
            CommentInterface::ATTR_EMAIL => $email,
            CommentInterface::ATTR_NAME => $name,
            CommentInterface::ATTR_PAPER_ID => $paper_id,
            CommentInterface::ATTR_CONTENT => $content,
            CommentInterface::ATTR_SHOW => true,
            CommentInterface::ATTR_PARENT_ID => $parentComment
        ];
        try {
            $this->commentForm->validate($commentData);
            $this->comment->forceFill($commentData)->save();
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage($e->getFullMessage()));
        } catch (\Exception $e) {
            $this->responseData->setMessage($e->getMessage());
            return $this->responseApi->setResponse($this->responseData)->setStatusCode(500);
        }
        return $this->responseApi->setResponse(
            $this->responseData->setResponse(
                $this->convertComment->convertItemData($this->comment)
            )->setMessage('add comment success!')
        );
    }

    /**
     * hiện tại không dùng qua hàm này để phản hồi comment
     * @param int $comment_id
     * @return ResponseApi
     */
    function paperReplayComment(int $comment_id)
    {
        $comment = $this->comment->find($comment_id);
        if (!$comment) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage('comment does not exist'));
        }

        $commentData = [
            CommentInterface::ATTR_EMAIL => $this->request->get(CommentInterface::ATTR_EMAIL),
            CommentInterface::ATTR_NAME => $this->request->get(CommentInterface::ATTR_NAME),
            CommentInterface::ATTR_PARENT_ID => $comment_id,
            CommentInterface::ATTR_CONTENT => $this->request->get(CommentInterface::ATTR_CONTENT),
            CommentInterface::ATTR_SHOW => true,
            CommentInterface::ATTR_PAPER_ID => $comment->{CommentInterface::ATTR_PAPER_ID}
        ];

        try {
            $this->commentForm->validate($commentData);
            /**
             * gán tất cả data mới trên 1 model đã có và ghi đè ngữ cảnh nó luôn.
             * có thể hiểu là làm mới model đó luôn.
             */
            $this->comment->forceFill($commentData)->save();
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage($e->getFullMessage()));
        } catch (\Exception $e) {
            $this->responseData->setMessage($e->getMessage());
            return $this->responseApi->setResponse($this->responseData)->setStatusCode(500);
        }

        return $this->responseApi->setResponse(
            $this->responseData->setResponse(
                $this->convertComment->convertItemData($this->comment)
            )->setMessage('add comment success!')
        );
    }

    /**
     * @param int $comment_id
     * @return ResponseApi|object
     */
    function commentLike($comment_id)
    {
        try {
            $comment = Comment::find($comment_id);
            if (!$comment) {
                throw new Exception('source comment not found!', 400);
            }
            $this->commentLikeForm->validate($this->request->all());
            $action = $this->request->get(CommentInterface::PARAM_ACTION);
            if ($action == CommentInterface::ACTION_LIKE) {
                $comment->like = $comment->like + 1;
            } elseif ($action == CommentInterface::ACTION_DISLIKE) {
                $comment->like = $comment->like - 1 <= 0 ? 0 : $comment->like - 1;
            }
            $comment->save();
        } catch (FormValidationException $e) {
            return $this->responseApi->setStatusCode(400)->setResponse($this->responseData->setMessage($e->getFullMessage()));
        } catch (\Throwable $th) {
            return $this->responseApi->setResponse($this->responseData->setMessage($th->getMessage()))->setStatusCode($th->getCode());
        }
        return $this->responseApi->setResponse($this->responseData->setMessage($action == CommentInterface::ACTION_LIKE ? 'đã thích!!' : 'bỏ thích')->setResponse(['count' => $comment->{CommentInterface::ATTR_LIKE}]));
    }

    /**
     * @param int $paper_id
     * @return ResponseApi
     */
    public function getCommentsOfPaper($paper_id)
    {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->commentApi->getCommentOfPaper($paper_id)));
    }

    /**
     * @param int $comment_id
     * @return ResponseApi
     */
    function getCommentChildrent(int $comment_id)
    {
        return $this->responseApi->setResponse($this->responseData->setResponse($this->commentApi->getCommentChildrent($comment_id)));
    }
}
