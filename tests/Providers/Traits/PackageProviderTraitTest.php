<?php namespace Orchestra\Support\Providers\Traits\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Support\Providers\Traits\PackageProviderTrait;

class PackageProviderTraitTest extends \PHPUnit_Framework_TestCase
{
    use PackageProviderTrait;

    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Container();
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->app = null;

        m::close();
    }

    /**
     * Test Orchestra\Support\Providers\Traits\PackageProviderTrait::package()
     * method.
     *
     * @test
     */
    public function testPackageMethod()
    {
        $this->app['config'] = $config = m::mock('\Orchestra\Contracts\Config\PackageRepository, \ArrayAccess');
        $this->app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $this->app['translator'] = $translator = m::mock('\Illuminate\Translation\Translator');
        $this->app['view'] = $view = m::mock('\Illuminate\Contracts\View\Factory');

        $path = '/var/www/vendor/foo/bar';

        $files->shouldReceive('isDirectory')->once()->with("{$path}/config")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with("{$path}/lang")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with("{$path}/views")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/var/www/resources/views/packages/foo/bar')->andReturn(true);

        $config->shouldReceive('package')->once()->with('foo/bar', "{$path}/config", 'foo')->andReturnNull()
            ->shouldReceive('get')->once()->with('view.paths', [])->andReturn(['/var/www/resources/views']);

        $translator->shouldReceive('addNamespace')->once()->with('foo', "{$path}/lang")->andReturnNull();

        $view->shouldReceive('addNamespace')->once()->with('foo', '/var/www/resources/views/packages/foo/bar')->andReturnNull()
            ->shouldReceive('addNamespace')->once()->with('foo', "{$path}/views")->andReturnNull();

        $this->assertNull($this->package('foo/bar', 'foo', $path));
    }

    /**
     * Test Orchestra\Support\Providers\Traits\PackageProviderTrait::hasPackageRepository()
     * method.
     *
     * @test
     */
    public function testHasPackageRepositoryMethod()
    {
        $this->app['config'] = m::mock('\Illuminate\Contracts\Config\Repository');
        $this->assertFalse($this->hasPackageRepository());

        $this->app['config'] = m::mock('\Orchestra\Contracts\Config\PackageRepository');
        $this->assertTrue($this->hasPackageRepository());
    }

    /**
     * Test Orchestra\Support\Providers\Traits\PackageProviderTrait::bootUsingLaravel()
     * method.
     *
     * @test
     */
    public function testBootUsingLaravelMethod()
    {
        $this->assertNull($this->bootUsingLaravel('foo'));
    }
}
