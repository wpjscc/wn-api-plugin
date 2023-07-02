<?php


namespace Wpjscc\Api\Traits;

use Wpjscc\Api\Helpers\ResponseEnum;
use Wpjscc\Api\Exceptions\BusinessException;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


trait ApiResponse
{
    /**
     * 成功
     * @param null $data
     * @param array $codeResponse
     * @return JsonResponse
     */
    public function success($data = null, $msg = '', $codeResponse = ResponseEnum::HTTP_OK): JsonResponse
    {
        if ($msg) {
            $codeResponse[1] = $msg;
        }
        return $this->jsonResponse('success',$codeResponse, $data, null);
    }

    /**
     * 失败
     * @param null $error
     * @param null $data
     * @param array $codeResponse
     * @return JsonResponse
     */
    public function fail($error=null, $data = null,  $codeResponse=ResponseEnum::HTTP_ERROR): JsonResponse
    {
        return $this->jsonResponse('fail', $codeResponse, $data, $error);
    }

    /**
     * json响应
     * @param $status
     * @param $codeResponse
     * @param $data
     * @param $error
     * @return JsonResponse
     */
    private function jsonResponse($status, $codeResponse, $data, $error): JsonResponse
    {
        list($code, $msg) = $codeResponse;
        return response()->json([
            'status'  => $status,
            'code'    => $code,
            'msg' => $msg,
            'data'    => $data ?? null,
            'error'  => $error,
        ]);
    }


    /**
     * 成功分页返回
     * @param $page
     * @return JsonResponse
     */
    protected function successPaginate($page): JsonResponse
    {
        return $this->success($this->paginate($page));
    }

    private function paginate($page)
    {
        if ($page instanceof LengthAwarePaginator){
            return [
                'total'  => $page->total(),
                'page'   => $page->currentPage(),
                'limit'  => $page->perPage(),
                'pages'  => $page->lastPage(),
                'list'   => $page->items()
            ];
        }
        if ($page instanceof Collection){
            $page = $page->toArray();
        }
        if (!is_array($page)){
            return $page;
        }
        $total = count($page);
        return [
            'total'  => $total, //数据总数
            'page'   => 1, // 当前页码
            'limit'  => $total, // 每页的数据条数
            'pages'  => 1, // 最后一页的页码
            'list'   => $page // 数据
        ];
    }

    /**
     * 业务异常返回
     * @param array $codeResponse
     * @param string $info
     * @throws BusinessException
     */
    public function throwBusinessException(array $codeResponse=ResponseEnum::HTTP_ERROR, string $info = '')
    {
        list($code, $msg) = $codeResponse;

        throw new BusinessException($msg, $code);
    }
}