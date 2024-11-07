<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleReputation;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function index()
    {
        return ArticleResource::collection(Article::paginate(10));
    }
/*************  ✨ Codeium Command ⭐  *************/
/******  c18e8f00-696a-494f-8692-ea2dedf4ff21  *******/
    public function store(ArticleRequest $request)
    {
        $validated = $request->validated();
        $validated['author_id'] = auth()->user()->id;
        $article = Article::create($validated);

        return response()->json([
            'data' => new ArticleResource($article),
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $validated = $request->validated();
        $article->update($validated);

        return response()->json([
            'data' => new ArticleResource($article),
        ]);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json([
            'data' => new ArticleResource($article),
        ]);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     *
     * Search articles
     */
    public function search($title)
    {
        $articles = Article::where('title', 'LIKE', "%$title%")->get();

        return ArticleResource::collection($articles);
    }


    public function vote(Request $request, Article $article)
    {
        $validated = $request->validate([
            'is_upvote' => 'required',
        ]);
        $article_rep = ArticleReputation::where('article_id', $article->id)->where('user_id', auth()->user()->id)->first();
        if (!isset($article_rep)) {
            ArticleReputation::create([
                'user_id' => auth()->user()->id,
                'article_id' => $article->id,
                'is_upvote' => $validated['is_upvote'],
            ]);
            return response()->json([
                'message' => $validated['is_upvote'] == true ? 'Article has been upvoted' : 'Article has been downvoted',
            ]);
        }
        if ($article_rep->is_upvote == $validated['is_upvote']) {
            $article_rep->delete();
            return response()->json([
                'message' => 'Article vote has been removed',
            ]);
        } else {
            $article_rep->is_upvote = $validated['is_upvote'];
            $article_rep->save();
            return response()->json([
                'message' => $validated['is_upvote'] == true ? 'Article has been upvoted' : 'Article has been downvoted',
            ]);
        }
    }
}
