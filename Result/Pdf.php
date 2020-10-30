<?php declare(strict_types=1);

namespace YireoTraining\ExamplePdfResult\Result;

use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\Controller\AbstractResult;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use YireoTraining\ExamplePdfResult\Exception\InvalidArgumentException;

class Pdf extends AbstractResult
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $contentDisposition = 'attachment';

    /**
     * @var FileDriver
     */
    private $driverFile;

    /**
     * Pdf constructor.
     * @param FileDriver $driverFile
     */
    public function __construct(
        FileDriver $driverFile
    ) {
        $this->driverFile = $driverFile;
    }

    /**
     * Set filename
     *
     * @param $fileName
     * @return Pdf
     */
    public function setFileName(string $fileName): Pdf
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param string $contentDisposition
     * @return Pdf
     */
    public function setContentDisposition(string $contentDisposition): Pdf
    {
        if (!in_array($contentDisposition, ['attachment', 'inline'])) {
            throw new InvalidArgumentException('Invalid PDF content disposition: "' . $contentDisposition . '"');
        }

        $this->contentDisposition = $contentDisposition;
        return $this;
    }


    protected function render(HttpResponseInterface $response)
    {
        $response->setHeader(
            'Content-Disposition',
            $this->contentDisposition . '; filename=' . basename($this->fileName) . ';'
        );

        $pdfContents = $this->getPdfContents();
        $response->setHeader('Cache-Control', 'private');
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Length', mb_strlen($pdfContents));
        $response->setBody($pdfContents);

        return $this;
    }

    /**
     * @return string
     * @throws FileSystemException
     */
    private function getPdfContents(): string
    {
        return $this->driverFile->fileGetContents($this->fileName);
    }
}
