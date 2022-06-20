<?php
/**
 * Swagger controller
 *
 * @category SwaggerController
 * @author   Adsum <developer@Adsum.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link    http://localhost/crypto_bull/api/
 */

/**
 * Swagger defination
 *
 * @OA\Info(
 *     version="3.1",
 *     title="ogas Project REST Service",@OA\Contact(name="ogas", url="http://localhost/crypto_bull/")
 * )
 * @OA\Server(
 *     description="Local Server",
 *     url="http://localhost/crypto_bull/api/"
 * ),
 * @OA\Server(
 *     description="Dev Server",
 *     url=""
 * ),
 * @OA\Server(
 *     description="Stage Server",
 *     url=""
 * ),
 * @OA\Server(
 *     description="Production Server",
 *     url=""
 * )
 */

/**
 * Swagger defination
 *
 * @OA\Schema(schema="SuccessResponse",
 * @OA\Property(property="data",type="array",@OA\Items()),
 * @OA\Property(property="message",type="string"))
 */

/**
 * Swagger defination
 *
 * @OA\Schema(schema="ErrorResponse",
 * @OA\Property(property="file",type="string"),
 * @OA\Property(property="line",type="string"),
 * @OA\Property(property="code",type="string"),
 * @OA\Property(property="message",type="string"),
 * @OA\Property(property="trace",type="string"),
 * @OA\Property(property="response",type="array",@OA\Items(type="string"))
 * )
 */

/**
 * Swagger defination
 *
 * @OA\Schema(schema="PagingData",
 * @OA\Property(property="current_page",type="integer",format="int64"),
 * @OA\Property(property="last_page",type="integer",format="int64"),
 * @OA\Property(property="per_page",type="integer",format="int64"),
 * @OA\Property(property="total",type="integer",format="int64"),
 * @OA\Property(property="last_page_url",type="string"),
 * @OA\Property(property="next_page_url",type="string"),
 * @OA\Property(property="prev_page_url",type="string")
 * )
 */

/**
 * Swagger defination for apikey
 *
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="API-Key",
 *     name="Authorization"
 * )
 */

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SwaggerController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * List all data
     *
     * @param Request  $request  request
     * @param int|null $parentId parent Id
     *
     * @return Response
     */
    public function listItem(Request $request, $parentId = null)
    {
        $swagger = \OpenApi\scan(base_path('app/'));
        return response()->json($swagger);
    }
}
